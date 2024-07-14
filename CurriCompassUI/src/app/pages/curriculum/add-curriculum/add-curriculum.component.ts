import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';

@Component({
  selector: 'app-add-curriculum',
  standalone: true,
  imports: [
    RouterLink,
    ReactiveFormsModule,
    FormsModule,
    CourseFilterPipe,
  ],
  providers: [
    CoursesServiceService,
    CourseFilterPipe,
  ],
  templateUrl: './add-curriculum.component.html',
  styleUrl: './add-curriculum.component.css'
})
export class AddCurriculumComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    private coursesService: CoursesServiceService,
    private coursePipe: CourseFilterPipe,
    public rs: RemoveInputErrorService,
    public dateformat: FormatDateService,
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchCourse: string ='';

  programs: any = null;
  school_years: any = null;
  courses: any = null;
  semesters: any = null;
  selectedCourses: Array<any> = [];
  year_levels: any = null;

  curriculum = this.fb.group({
    programid : new FormControl(null, [Validators.required]),
    specialization : new FormControl(null),
    sy : new FormControl(null, [Validators.required]),
    curriculum_subjects: this.fb.array([]),
  });

  onInputChange(event: any) {
    if (event.target.value.trim().length <= 0) {
      this.curriculum.get('specialization')!.setValue(null);
    }
  }

  get csubjectsFormArray() {
    return this.curriculum.get('curriculum_subjects') as FormArray;
  }

  popCsubjectsArray(index : number){
    this.selectedCourses.splice(index, 1);
    this.fac.popControl(this.csubjectsFormArray, index);
  }

  courseSelected(index: number) {
    const courseid = this.getCsubjectsControl(index)?.value;
    this.selectedCourses[index] = parseInt(courseid);
  }

  isCourseSelected(courseid: number): boolean {
    return this.selectedCourses.includes(courseid);
  }

  addCsubjectsArray() {
    const csubject: any = this.fb.group({
      'subjectid' : new FormControl(null, [Validators.required]),
      'semid' : new FormControl(null, [Validators.required]),
      'year_level_id' : new FormControl(null, [Validators.required]),
    });
    this.fac.addControl(this.csubjectsFormArray, csubject);
  }

  getYearLevelControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.csubjectsFormArray, "year_level_id");
  }

  getCsubjectsControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.csubjectsFormArray, "subjectid");
  }

  getCsemControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.csubjectsFormArray, "semid");
  }

  isSelectedCourseFiltered(index: number): boolean {
    // const selectedCourseId = parseInt(this.getCsubjectsControl(index).value);
    const selectedCourseId = this.getCsubjectsControl(index).value;
    if (typeof selectedCourseId != "number") {
      return false;
    }
    const filteredCourses = this.coursePipe.transform(this.courses, this.searchCourse);
    return !filteredCourses.find(course => course.subjectid === selectedCourseId)? true : false;
  }

  getSelectedCourse(i: number){
    return this.courses.find((c:any) => c.subjectid == i);
  }

  handleSubmit(){

    if(this.curriculum.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.curriculum);
      return;
    }

    if(this.csubjectsFormArray.length <= 0 ){
      return;
    }

    this.req.postResource('curriculum', this.curriculum.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/curricula');
      },

      error: err => {
        this.curriculum.get('specialization')?.setErrors({duplicate: true});
        console.error(err)
      },
    })
  }

  ngOnInit(){
    this.req.getResource('year-level', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any)=> {
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

    this.coursesService.getCourses().subscribe({
      next: (c:any) => {
        this.courses = c;
      },
      error: (err:any) => console.log(err),
   })

    this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.semesters = res[1];
      },
      error: err => console.error(err),
    })
  }
}
