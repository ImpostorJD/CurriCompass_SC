import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { CourseFilterPipe } from '../../services/search-filters/course-pipe.pipe';
import { CoursesServiceService } from '../../services/courses-service.service';
import { FormArrayControlUtilsService } from '../../services/form-array-control-utils.service';
import { FormatDateService } from '../../services/format/format-date.service';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-edit-curriculum',
  standalone: true,
  imports: [
    RouterLink,
    ReactiveFormsModule,
    HttpClientModule,
    CourseFilterPipe,
    FormsModule,
  ],
  providers: [
    HttpReqHandlerService,
    RemoveInputErrorService,
    CoursesServiceService,
    CourseFilterPipe,
  ],
  templateUrl: './edit-curriculum.component.html',
  styleUrl: './edit-curriculum.component.css'
})
export class EditCurriculumComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    private coursesService: CoursesServiceService,
    private coursePipe: CourseFilterPipe,
    private req: HttpReqHandlerService,
    public rs: RemoveInputErrorService,
    private activatedRoute: ActivatedRoute,
    public dateformat: FormatDateService,
    private auth: AuthService,
  ){}

  searchCourse: string ='';

  school_years: any = null;
  routeId:number = null!;
  programs: any = null;
  courses: any = null;
  semesters: any = null;
  selectedCourses: Array<any> = [];

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

  courseSelected(index: number, event: any) {
    const courseid = event.target.value;
    this.selectedCourses[index] = parseInt(courseid);
  }

  isCourseSelected(courseid: number): boolean {
    return this.selectedCourses.includes(courseid);
  }

  addCsubjectsArray() {
    const csubject: any = this.fb.group({
      'subjectid' : new FormControl(null, [Validators.required]),
      'semid' : new FormControl(null, [Validators.required]),
      'year_level' : new FormControl(null, [Validators.required]),
    });
    this.fac.addControl(this.csubjectsFormArray, csubject);
  }

  getYearLevelControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.csubjectsFormArray, "year_level");
  }

  getCsubjectsControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.csubjectsFormArray, "subjectid");
  }

  getCsemControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.csubjectsFormArray, "semid");
  }

  isSelectedCourseFiltered(index: number): boolean {
    const selectedCourseId = parseInt(this.getCsubjectsControl(index).value);
    if (typeof selectedCourseId != "number") {
      return false;
    }
    const filteredCourses = this.coursePipe.transform(this.courses, this.searchCourse);
    return typeof filteredCourses.find(course => course.subjectid === selectedCourseId) == "undefined" ? false: true;
  }

  getSelectedCourse(i: number){
    return this.courses.find((c:any) => c.subjecid = i);
  }

  handleSubmit(){

    if(this.curriculum.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.curriculum);
      return;
    }

    if(this.csubjectsFormArray.length <= 0 ){
      return;
    }

    this.req.patchResource('curriculum/' + this.routeId, this.curriculum.value, httpOptions).subscribe({
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
    });

    this.activatedRoute.params.subscribe(params => {
      this.routeId = params['id'];
      this.req.getResource('curriculum/' + this.routeId, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: (res:any) => {
          this.curriculum.patchValue(res[1]);
          if(res[1].curriculum_subjects.length > 0) {
            res[1].curriculum_subjects.forEach((cs:any, index: number) => {
              const csubject: any = this.fb.group({
                'subjectid' : new FormControl(cs.subjectid, [Validators.required]),
                'semid' : new FormControl(cs.semid, [Validators.required]),
                'year_level' : new FormControl(cs.year_level, [Validators.required]),
              });
              this.csubjectsFormArray.push(csubject);
              this.selectedCourses[index] = parseInt(cs.subjectid);
            });
          }

        },
        error: err => console.error(err),

      });
    });
  }

}
