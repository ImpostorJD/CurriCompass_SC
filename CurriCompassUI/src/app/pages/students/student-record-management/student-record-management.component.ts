import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { httpOptions, markFormGroupAsDirtyAndInvalid, sortSemester, yearLevel } from '../../../../configs/Constants';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { allOrNoneValidator } from '../../../services/validators/all-or-none.validator';

@Component({
  selector: 'app-student-record-management',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    RouterLink,
  ],
  providers: [
    RemoveInputErrorService,
  ],
  templateUrl: './student-record-management.component.html',
  styleUrl: './student-record-management.component.css'
})
export class StudentRecordManagementComponent {
  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    public rs: RemoveInputErrorService,
    public dateformat: FormatDateService,
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  routeId: string = null!;
  studentProfile: any = null;

  programs:any = null;
  specializations: any = null;

  school_years: any = null;
  curricula:any = null;
  curriculumSubjects:any = null;
  subjectTaken: any = null;
  year_levels: any = null;

  studentProfileField =  this.fb.group({
    "studentid" : new FormControl('', [Validators.required]),
    "userfname" : new FormControl('', [Validators.required]),
    "userlname" : new FormControl('', [Validators.required]),
    "usermiddle" : new FormControl('', [Validators.required]),
    "sy": new FormControl(null, [Validators.required]),
    "contact_no" : new FormControl('', [Validators.required, Validators.pattern(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)]),
    "email" : new FormControl('', [Validators.required, Validators.email]),
    "program" : new FormControl('', [Validators.required]),
    "specialization" : new FormControl(null),
    "year_level_id" : new FormControl(null, [Validators.required]),
    "status" : new FormControl(null, [Validators.required]),
    "subjects_taken" : this.fb.array([]),
  });

  get subjectsTakenArray() {
    return this.studentProfileField.get('subjects_taken') as FormArray;
  }

  addSubjectsTaken(element: any){
    const subject_taken = this.studentProfile.student_record.subjects_taken;
    let subject = subject_taken.length > 0 ?
      subject_taken.find((s:any) => s.subjectid === element.subjectid) : null;
    let taken_at = null;
    let remark = null;
    let sy = null;
    let grade = null;

    if(subject != null && typeof subject != "undefined"){
      taken_at = subject.taken_at;
      remark = subject.remark;
      sy = subject.sy;
    }

    const subjectField = this.fb.group({
      "subjectid": new FormControl(element.subjectid),
      "taken_at": new FormControl(taken_at),
      "remark": new FormControl(remark),
      "sy": new FormControl(sy),
      "grade": new FormControl(grade),
    },  { validators: [allOrNoneValidator(['remark', 'grade', 'taken_at', 'sy'], 'subjectid')] });

    this.fac.addControl(this.subjectsTakenArray, subjectField);
  }

  popSubjectsTaken(index: number){
    this.fac.popControl(this.subjectsTakenArray, index);
  }

  clearAllSubjectsTaken(){
    this.fac.clearControls(this.subjectsTakenArray);
  }

  getRemarkControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.subjectsTakenArray, 'remark');
  }

  getGradeControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.subjectsTakenArray, 'grade');
  }

  getTakenAtControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.subjectsTakenArray, 'taken_at');
  }

  getSchoolYearControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsTakenArray, 'sy');
  }

  getGradeOfSubject(subjectid: number) {
    return this.curriculumSubjects.find((s:any) => s.subjectid === subjectid).grade;
  }

  getRemarkOfSubject(subjectid: number) {
    return this.curriculumSubjects.find((s:any) => s.subjectid === subjectid).remark;
  }

  changeGradeRemark(index: number){

    if (this.getGradeControl(index).invalid) return;

    const grade = this.getGradeControl(index).value;
    let remark = grade == 1 ? "Excellent" :
    (grade == 1.25 || grade == 1.50 ? "Very Good" :
    (grade == 1.75 || grade == 2 || grade == 2.25 ? "Good" :
    (grade == 2.5 ? "Fair" :
    (grade == 2.75 || grade == 3 ? "Passing" : "Failed"))));

    this.getRemarkControl(index).patchValue(remark);
  }

  getCurriculumSubjects(id: number){
    this.clearAllSubjectsTaken();
    this.req.getResource('curriculum/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.curriculumSubjects = res[1].curriculum_subjects
        .sort((a: any, b: any) =>  sortSemester(a.semesters.semdesc, b.semesters.semdesc))
        .sort((a: any, b: any) => yearLevel(a.year_level.year_level_desc, b.year_level.year_level_desc));
        this.curriculumSubjects.forEach((element:any) => {
          this.addSubjectsTaken(element);
        });
      },
      error: (err: any) => console.error(err),
    });
  }

  setSpecializationsEvent(event: any){
    this.curriculumSubjects = null;
    this.setSpecializations(parseInt(event.target.value));
    this.changeSpecialization();
  }

  changeSpecialization() {
    const specialization = this!.studentProfileField!.get('specialization')!.value != "null" ?
    this.studentProfileField.get('specialization')?.value
      : null;
    const programid = parseInt(this!.studentProfileField!.get('program')!.value!);
    this.getCurriculumSubjects(this.curricula.find((e:any) => e.specialization === specialization && e.programid === programid)!.cid);
  }

  setSpecializations(programid: number){
    this.specializations = this.curricula
      .filter((e:any) => e.program.programid === programid)
      .sort((a:any, b:any) => {
        if (a.specialization === null) {
          return -1;
        } else if (b.specialization === null) {
          return 1;
        }
        return a.specialization.localeCompare(b.specialization);
      });
      this!.studentProfileField!.get('specialization')!.patchValue(null);
  }

  handleSubmit(){
    if(this.studentProfileField.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.studentProfileField);
      return;
    }

    let data = this.studentProfileField.value;
    let remarkFilled = true;

    data.subjects_taken!.forEach((e:any, index: number) => {
      if(e.taken_at != null && e.remark == null) {
        this.getRemarkControl(index).setErrors({"required": true});
        remarkFilled = false;
      }
    });

    if (!remarkFilled) return;

    data.subjects_taken = data.subjects_taken!.filter((e:any) => e.taken_at !== null)!;
    this.req.patchResource('student-records/' + this.studentProfile.userid, data, httpOptions(this.auth.getCookie('user')))
      .subscribe({
        next: () => {
          this.router.navigateByUrl('/students');
        },

        error: (err:any) => {
          if(err.status == 409) {
            if(err.error[1].email != null){
              this.studentProfileField.get('email')?.setErrors({duplicate: true});
            }
            if(err.error[1].studentid != null){
              this.studentProfileField.get('studentid')?.setErrors({duplicate: true});
            }
          }
        },
      });
  }

  ngOnInit() {
    this.req.getResource('curriculum', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.curricula = res[1];
      },
      error: (err: any) => console.error(err),
    });

    this.req.getResource('year-level', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.year_levels = res[1];
      },
      error: (err: any) => console.error(err),
    })

    this.req.getResource('programs', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.programs = res[1];
      },
      error: (err: any) => console.error(err),
    });

    this.req.getResource('school-year', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.school_years = res[1];
      },
      error: err => console.error(err),
    });

    this.activatedRoute.params.subscribe(params => {
      this.routeId = params["id"];
      this.req.getResource('student-records/' + this.routeId, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: (res: any) => {
          this.studentProfile = res[1];
          this.studentProfileField.patchValue(this.studentProfile);
          this.studentProfileField.get('studentid')?.patchValue(this.studentProfile.student_record.student_no);
          this.studentProfileField.get('year_level_id')?.patchValue(this.studentProfile.student_record?.year_level?.year_level_id);
          this.studentProfileField.get('status')?.patchValue(this.studentProfile.student_record.status);
          this.studentProfileField.get('sy')?.patchValue(this.studentProfile.student_record.sy);
          this.subjectTaken = this.studentProfile.student_record.subjects_taken;

          if (this.studentProfile.student_record.cid != null) {
            this.studentProfileField.get('program')?.patchValue(this.studentProfile.student_record.curriculum.program.programid);
            this.setSpecializations(this.studentProfile.student_record.curriculum.program.programid);
            this.studentProfileField.get('specialization')?.patchValue(this.studentProfile.student_record.curriculum.specialization);
            this.getCurriculumSubjects(this.studentProfile.student_record.cid);
          }
        },
        error: (err: any) => console.error(err),
      })
    })
  }
}
