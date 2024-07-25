import { Component, inject } from '@angular/core';
import { Form, FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { CurriculumCourseTableComponent } from '../../../components/curriculum-course-table/curriculum-course-table.component';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-add-curriculum',
  standalone: true,
  imports: [
    RouterLink,
    ReactiveFormsModule,
    FormsModule,
    CourseFilterPipe,
    CurriculumCourseTableComponent,
    CommonModule
  ],
  providers: [
    CoursesServiceService,
    CourseFilterPipe,
  ],
  templateUrl: './add-curriculum.component.html',
  styleUrls: ['./add-curriculum.component.css']
})
export class AddCurriculumComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    private coursePipe: CourseFilterPipe,
    public rs: RemoveInputErrorService,
    public dateformat: FormatDateService,
  ) {}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  programs: any = null;
  school_years: any = null;
  semesters: any = null;
  year_levels: any = null;
  error: boolean = false;
  errorMessage: any[] = [];
  selectedTable:FormGroup | null = null;
  selectedIndex: number = -1;

  currentLevel = 1;
  currentSemester = 1;
  maxLevel = 4;
  maxSemester = 3;

  curriculum = this.fb.group({
    programid: new FormControl(null, [Validators.required]),
    specialization: new FormControl(null),
    sy: new FormControl(null, [Validators.required]),
    curriculum_subjects: this.fb.array([]),
  });

  onInputChange(event: any) {
    if (event.target.value.trim().length <= 0) {
      this.curriculum.get('specialization')!.setValue(null);
    }
  }

  setCurriculumSubject(event: any) {

    // Use setControl to replace the FormGroup at the selected index
    this.csubjectsFormArray.setControl(this.selectedIndex, event as FormGroup);

    // Reset the selectedTable if needed
    this.selectedTable = null;
  }

  get csubjectsFormArray() {
    return this.curriculum.get('curriculum_subjects') as FormArray;
  }

  getsubjectsFormArray(index: number) {
    return this.csubjectsFormArray.at(index).get('subjects') as FormArray;
  }

  popCsubjectsArray(index: number) {
    this.fac.popControl(this.csubjectsFormArray, index);
    this.updateLevelsAndSemesters();
  }

  totalSum(index:number, controlname:string){
    let total = 0;
    const formArray = this.getsubjectsFormArray(index);
    formArray.controls.forEach((c) => {
      const value = parseFloat(c.get(controlname)?.value);
      total+= value;
    });

    return Math.round(total * 100)/100 ;
  }

  addCsubjectsArray() {
    const csubject: any = this.fb.group({
      'subjects': this.fb.array([]),
      'level': this.currentLevel,
      'semester': this.currentSemester
    });

    this.fac.addControl(this.csubjectsFormArray, csubject);
    this.incrementLevelAndSemester();
  }

  incrementLevelAndSemester() {
    if (this.currentSemester < this.maxSemester) {
      this.currentSemester++;
    } else {
      this.currentSemester = 1;
      this.currentLevel++;
    }
  }

  enableModalAndSetEditable(index: number) {
    this.selectedIndex = index;
    this.selectedTable = this.csubjectsFormArray.at(index) as FormGroup;
  }

  updateLevelsAndSemesters() {
    this.currentLevel = 1;
    this.currentSemester = 1;

    for (let i = 0; i < this.csubjectsFormArray.length; i++) {
      const csubject = this.csubjectsFormArray.at(i);
      csubject.get('level')?.setValue(this.currentLevel);
      csubject.get('semester')?.setValue(this.currentSemester);

      this.incrementLevelAndSemester();
    }
  }

  totalUnits(){
    let total = 0;
    this.csubjectsFormArray.controls.forEach((e, index) => {
      const formArray = this.getsubjectsFormArray(index);
      formArray.controls.forEach((c) => {
        const value = parseFloat(c.get('units')?.value);

        total+= value;
      });
    });

    return Math.round(total * 100)/100 ;
  }

  getCsubjectsControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.csubjectsFormArray, "subjects");
  }

  resetError(){
    this.errorMessage = [];
    this.error = false;
  }

  handleSubmit() {
    if (this.curriculum.status === "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.curriculum);
      return;
    }

    if (this.csubjectsFormArray.length <= 0) {

      return;
    }

    this.csubjectsFormArray.controls.forEach(c => {
      let issueFound = false;
      let innerControl = c!.get('subjects') as FormArray;
      let level = "LEVEL " + (c.get('level')?.value === 1 ? 'I' :
        (c.get('level')?.value === 2 ? 'II' :
        (c.get('level')?.value === 3 ? 'III' :
        'IV')));
      let sem = (c.get('semester')?.value === 1 ? '1ST' :
        (c.get('semester')?.value === 2 ? '2ND' :
        '3RD')) + " TRIMESTER";

      if (innerControl.controls.length <= 0) {
        this.error = true;
        issueFound = true;
        if (issueFound) {
          this.errorMessage.push(`${level} ${sem}: No courses had been added yet.`);
        }
      }

      innerControl.controls.forEach((maincontrol) => {
        let prval = maincontrol.get('prerequisites')?.value;
        if (prval){
          let pr = prval.split(' & ');
          let filtered = pr.filter((string:any) => {
            return !string.includes("STANDING");
          });

          filtered.forEach((string:any) => {
            let subject = null;
            this.csubjectsFormArray.controls.forEach(c => {
              let inc = c.get('subjects') as FormArray;
              inc.controls.forEach((control) => {
                let code = control.get('coursecode')?.value;
                console.log(code);
                if (code == string){
                  subject = code;
                }
              });
            })

            console.log(subject);

            if(!subject){
              this.error = true;
              issueFound = true;
              this.errorMessage.push(`${maincontrol.get('coursecode')?.value}: Has a nonexistent prerequisite course/s ${string}`);
            }
          });
        }
      });

    });

    if (this.error) {
      return;
    }

    this.req.postResource('curriculum', this.curriculum.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/curricula');
      },
      error: err => {
        this.curriculum.get('specialization')?.setErrors({ duplicate: true });
        console.error(err);
      }
    });
  }

  canAddMoreTables() {
    return this.currentLevel < this.maxLevel || (this.currentLevel === this.maxLevel && this.currentSemester <= this.maxSemester);
  }

  ngOnInit() {
    this.req.getResource('year-level', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.year_levels = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('programs', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.programs = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('school-year', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.school_years = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.semesters = res[1];
      },
      error: err => console.error(err),
    });
  }

}
