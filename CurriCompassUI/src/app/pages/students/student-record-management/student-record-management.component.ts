import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { httpOptions, markFormGroupAsDirtyAndInvalid, sortSemester, yearLevel } from '../../../../configs/Constants';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { allOrNoneValidator } from '../../../services/validators/all-or-none.validator';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';
import { idValidator } from '../../../services/validators/id-validator';
import { emailDomainValidator } from '../../../services/validators/domain-validator';

@Component({
  selector: 'app-student-record-management',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    RouterLink,
    LoadingComponentComponent,
    FormsModule
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
    public loading: SystemLoadingService,
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  routeId: string = null!;
  studentProfile: any = null;

  programs:any = null;
  specializations: any = null;

  school_years: any = null;
  curricula:any = null;
  curriculumSubjects:any = [];
  subjectTaken: any = null;
  year_levels: any = null;
  gradeEditable: boolean = false;

  studentProfileField =  this.fb.group({
    "studentid" : new FormControl('', [Validators.required, idValidator()]),
    "userfname" : new FormControl('', [Validators.required, Validators.pattern(/^[A-Za-zÀ-ÖØ-öø-ÿ]+([ \'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*[A-Za-zÀ-ÖØ-öø-ÿ]$/)]),
    "userlname" : new FormControl('', [Validators.required, Validators.pattern(/^[A-Za-zÀ-ÖØ-öø-ÿ]+([ \'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*[A-Za-zÀ-ÖØ-öø-ÿ]$/)]),
    "usermiddle" : new FormControl('', [Validators.pattern(/^[A-Za-zÀ-ÖØ-öø-ÿ]+([ \'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*[A-Za-zÀ-ÖØ-öø-ÿ]$/)]),
    "sy": new FormControl(null, [Validators.required]),
    "contact_no" : new FormControl('', [Validators.required, Validators.pattern(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)]),
    "email" : new FormControl('', [Validators.required, Validators.email, emailDomainValidator()]),
    "curriculum" : new FormControl('', [Validators.required]),
    "subjects_taken" : this.fb.array([]),
  });

  get subjectsTakenArray() {
    return this.studentProfileField.get('subjects_taken') as FormArray;
  }

  getSubjectTakenValue(coursecode: string) {
    const value = this.subjectTaken.find((s:any) => s.coursecode == coursecode);
    return value ? value.grade : '';
  }

  handleGradeControl(coursecode: string, event:any) {

    let value = event.target.value;

    let subjectFound = false;
    this.subjectsTakenArray.controls.forEach((control, index) => {
      if(control.get('coursecode')?.value == coursecode){
        subjectFound = true;
        if(value != '') {
          control.get('grade')?.patchValue(value);
        }else{
          this.subjectsTakenArray.removeAt(index);
        }
      }

    });

    if(subjectFound) return;

    if(value != "None") {
      const subjectField = this.fb.group({
        "coursecode": new FormControl(coursecode),
        "grade": new FormControl(value),
      });

      this.fac.addControl(this.subjectsTakenArray, subjectField);
    }
  }


  grades = ['1', '1.25', '1.5', '1.75', '2', '2.25', '2.5', '2.75', '3', '5', 'x', 'w'];

  handleSubmit(){

    if(this.studentProfileField.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.studentProfileField);
      return;
    }

    // this.enableGradeAndRemarkControls();

    let data = this.studentProfileField.value;
    data.subjects_taken = data.subjects_taken?.filter((e:any) => e.grade != '');

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

  changeCurriculum(){
    this.loading.initLoading();
    this.req.getResource('curriculum/' + this.studentProfileField.get('curriculum')?.value , httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.curriculumSubjects = [];
        const csub = res[1];

        csub.curriculum_subjects.forEach((e: any) => {
          const table:any = this.curriculumSubjects.find((a:any) => a.semester == e.semid && a.year == e.year_level_id)

          if(table){
            table['subjects'].push(e);
          }else{
            const table:any = {"year" : e.year_level_id, "semester" : e.semid, "subjects" : []}
            table['subjects'].push(e);
            this.curriculumSubjects.push(table);
          }

          this.curriculumSubjects.sort((a:any, b:any) => {
            const aLevel = a.year;
            const bLevel = b.year;
            const aSemester = a.semester;
            const bSemester = b.semester;

            if (aLevel === bLevel) {
              return aSemester - bSemester;
            }
            return aLevel - bLevel;
          });

        });

      },
      error: (err: any) => console.error(err),
      complete: () => this.loading.endLoading(),
    });
  }

  totalSum(index:number, controlname:string){
    let total = 0;

    this.curriculumSubjects[index]['subjects'].forEach((c:any) => {
      const value = parseFloat(c[controlname]);
      total+= value;
    });

    return Math.round(total * 100) /100;
  }

  async ngOnInit() {

    this.loading.initLoading();
    const user = await this.auth.getUser();
    user.user_roles.forEach((e:any) => {
      if (e.rolename == "Admin"){
        this.gradeEditable = true;
      }
    });

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
          this.studentProfileField.get('sy')?.patchValue(this.studentProfile.student_record.sy);
          this.studentProfileField.get('curriculum')?.patchValue(this.studentProfile.student_record.cid);
          this.subjectTaken = this.studentProfile.student_record.subjects_taken;

          this.subjectTaken.forEach((e:any) => {
            const subjectField = this.fb.group({
              "coursecode": new FormControl(e.coursecode),
              "grade": new FormControl(e.grade),
            });

            this.fac.addControl(this.subjectsTakenArray, subjectField);
          });

          this.studentProfile.student_record.curriculum.curriculum_subjects.forEach((e: any) => {
            const table:any = this.curriculumSubjects.find((a:any) => a.semester == e.semid && a.year == e.year_level_id)

            if(table){
              table['subjects'].push(e);
            }else{
              const table:any = {"year" : e.year_level_id, "semester" : e.semid, "subjects" : []}
              table['subjects'].push(e);
              this.curriculumSubjects.push(table);
            }

          });
          this.curriculumSubjects.sort((a:any, b:any) => {
            const aLevel = a.year;
            const bLevel = b.year;
            const aSemester = a.semester;
            const bSemester = b.semester;

            if (aLevel === bLevel) {
              return aSemester - bSemester;
            }
            return aLevel - bLevel;
          });
        },
        error: (err: any) => console.error(err),
        complete: () => this.loading.endLoading(),
      })
    })

  }
}
