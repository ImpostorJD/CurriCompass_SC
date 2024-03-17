import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { httpOptions, markFormGroupAsDirtyAndInvalid, sortSemester, yearLevel } from '../../../configs/Constants';

@Component({
  selector: 'app-student-record-management',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterLink,
  ],
  providers: [
    HttpReqHandlerService,
    RemoveInputErrorService,
  ],
  templateUrl: './student-record-management.component.html',
  styleUrl: './student-record-management.component.css'
})
export class StudentRecordManagementComponent {
  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private req: HttpReqHandlerService,
    private fb: FormBuilder,
    public rs: RemoveInputErrorService
  ){}

  routeId: string = null!;
  studentProfile: any = null;

  programs:any = null;
  specializations: any = null;

  curricula:any = null;
  curriculumSubjects:any = null;
  subjectTaken: any = null;

  studentProfileField =  this.fb.group({
    "studentid" : new FormControl('', [Validators.required]),
    "userfname" : new FormControl('', [Validators.required]),
    "userlname" : new FormControl('', [Validators.required]),
    "usermiddle" : new FormControl('', [Validators.required]),
    "contact_no" : new FormControl('', [Validators.required, Validators.pattern(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)]),
    "email" : new FormControl('', [Validators.required, Validators.email]),
    "program" : new FormControl('', [Validators.required]),
    "specialization" : new FormControl(null, [Validators.required]),
    "year_level" : new FormControl(null, [Validators.required]),
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

    if(subject != null && typeof subject != "undefined"){
      taken_at = subject.taken_at;
      remark = subject.remark;
    }

    const subjectField = this.fb.group({
      "subjectid": new FormControl(element.subjectid),
      "taken_at": new FormControl(taken_at),
      "remark": new FormControl(remark),
    });

    this.subjectsTakenArray.push(subjectField);
  }

  popSubjectsTaken(index: number){
    this.subjectsTakenArray.removeAt(index);
  }

  clearAllSubjectsTaken(){
    this.subjectsTakenArray.clear();
  }

  getRemarkControl(index: number): FormControl{
    return (this.subjectsTakenArray.at(index) as FormGroup).get('remark')! as FormControl;
  }

  getTakenAtControl(index: number): FormControl{
    return (this.subjectsTakenArray.at(index) as FormGroup).get('taken_at')! as FormControl;
  }

  getRemarkOfSubject(subjectid: number) {
    return this.curriculumSubjects.find((s:any) => s.subjectid === subjectid).remark;
  }

  getCurriculumSubjects(id: number){
    this.clearAllSubjectsTaken();
    this.req.getResource('curriculum/' + id, httpOptions).subscribe({
      next: (res: any) => {
        this.curriculumSubjects = res[1].curriculum_subjects
          .sort((a: any, b: any) => yearLevel(a.year_level, b.year_level))
          .sort((a: any, b: any) =>  sortSemester(a.semesters.semdesc, b.semesters.semdesc));
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
    this.getCurriculumSubjects(this.curricula.find((e:any) => e.specialization === specialization && e.programid === programid).cid);
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

    console.log(this.studentProfileField.value);

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

    console.log(this.studentProfileField);
    if (!remarkFilled) return;

    data.subjects_taken = data.subjects_taken!.filter((e:any) => e.taken_at !== null)!;
    this.req.patchResource('student-records/' + this.studentProfile.userid, data, httpOptions)
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
    this.req.getResource('curriculum', httpOptions).subscribe({
      next: (res: any) => {
        this.curricula = res[1];
      },
      error: (err: any) => console.error(err),
    });

    this.req.getResource('programs', httpOptions).subscribe({
      next: (res: any) => {
        this.programs = res[1];
      },
      error: (err: any) => console.error(err),
    });

    this.activatedRoute.params.subscribe(params => {
      this.routeId = params["id"];
      this.req.getResource('student-records/' + this.routeId, httpOptions).subscribe({
        next: (res: any) => {
          this.studentProfile = res[1];
          this.studentProfileField.patchValue(this.studentProfile);
          this.studentProfileField.get('studentid')?.patchValue(this.studentProfile.student_record.student_no);
          this.studentProfileField.get('year_level')?.patchValue(this.studentProfile.student_record.year_level);
          this.studentProfileField.get('status')?.patchValue(this.studentProfile.student_record.status);
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
