import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { AuthService } from '../../../services/auth/auth.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-edit-course',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
    CourseFilterPipe,
    FormsModule,
    LoadingComponentComponent,
  ],
  providers: [
    CourseFilterPipe,
    CoursesServiceService,
  ],
  templateUrl: './edit-course.component.html',
  styleUrl: './edit-course.component.css'
})
export class EditCourseComponent {
  constructor(
    private router: Router,
    private activeRouter: ActivatedRoute,
    private fb: FormBuilder,
    private coursePipe: CourseFilterPipe,
    private coursesService: CoursesServiceService,
    private fac: FormArrayControlUtilsService,
    public rs: RemoveInputErrorService,
    public loading: SystemLoadingService
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchCourse: string = '';
  courseList: any = null;
  selectedCourses: Array<any> = [];
  semesters:any = null;
  year_levels:any = null;

  routerId: number = null!;

  courseField = this.fb.group({
    subjectcode: new FormControl('', [Validators.required]),
    subjectname: new FormControl('', [Validators.required]),
    subjectcredits: new FormControl('', [Validators.required, Validators.pattern("^[1-9]*$")]),
    subjectunitlab: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    subjectunitlec: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    subjecthourslec: new FormControl('', [Validators.required, Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
    subjecthourslab: new FormControl('', [Validators.required, Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
    // semavailability: new FormControl(null, [Validators.required]),
    //completion: new FormControl(null, [Validators.min(0), Validators.max(1)]),
    year_level_id: new FormControl(null),
    subjects: this.fb.array([]),
  });

  get reqCourseArray() {
    return this.courseField.get('subjects') as FormArray;
  }

  popReqCourseArray(index : number){
    this.selectedCourses.splice(index, 1);
    this.fac.popControl(this.reqCourseArray, index);
  }

  courseSelected(index: number) {
    const courseid = this.getReqCourseControl(index).value;
    this.selectedCourses[index] = parseInt(courseid);
  }


  isCourseSelected(courseid: number): boolean {
    const course:any = this.selectedCourses.find((c:number) =>  c === courseid);
    return (course != null && typeof course != "undefined");
  }

  addReqCourseArray() {
    const csubject = this.fb.group({
      'subjectid' : new FormControl(null, [Validators.required]),
    });
    this.fac.addControl(this.reqCourseArray, csubject);
  }

  isSelectedCourseFiltered(index: number): boolean {
    const selectedCourseId = parseInt(this.getReqCourseControl(index).value);
    if (typeof selectedCourseId != "number") {
      return false;
    }
    const filteredCourses = this.coursePipe.transform(this.courseList, this.searchCourse);
    return typeof filteredCourses.find(course => course.subjectid === selectedCourseId) == "undefined" ? false: true;

  }

  getSelectedCourse(i: number | string) {
    if (typeof i == "string"){
      i = parseInt(i);
    }
    return this.courseList.find((c:any) => c.subjectid === i);
  }

  getReqCourseControl(index: number): FormControl{
    return (this.reqCourseArray.at(index) as FormGroup).get('subjectid')! as FormControl;
  }

  handleSubmit(){

    if(this.courseField.status == "INVALID"){
      markFormGroupAsDirtyAndInvalid(this.courseField);
      return;
    }

    this.req.patchResource('subjects/' + this.routerId, this.courseField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/courses');
      },
      error: (err:any) => {
        if(err.status === 409) {
          this.courseField.get('subjectcode')!.setErrors({duplicate: true});
          console.log(this.courseField);

        }
      }
    })
  }

  ngOnInit(){
    this.loading.initLoading();
    this.coursesService.getCourses().subscribe({
      next: (c:any) => {
        this.courseList = c;
      },
      error: (err:any) => console.log(err),
    });
    this.req.getResource('year-level', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (s:any) => {
            this.year_levels = s[1];
          },
      error: (err:any) => console.log(err),
    });
    // this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
    //   next: (s:any) => {
    //     this.semesters = s[1];
    //   },
    //   error: (err:any) => console.log(err),
    //  });

    this.activeRouter.params.subscribe(params => {
      this.routerId = parseInt(params['id']);

      this.coursesService.getCourse(this.routerId).subscribe({
        next: (c:any) => {
          this.courseField.patchValue(c);
          this.courseField.controls['year_level_id'].setValue(c.pre_requisites?.year_level?.year_level_id ? c.pre_requisites?.year_level?.year_level_id : null);
          //this.courseField.controls['completion'].setValue(c.pre_requisites.completion);
          //this.courseField.controls['semavailability'].setValue(c.course_availability.semid);
          if (c.pre_requisites.pre_requisites_subjects.length > 0) {
            c.pre_requisites.pre_requisites_subjects.forEach((subject:any, i: number) => {
              const csubject: any = this.fb.group({
                'subjectid' : new FormControl(parseInt(subject.subjectid), [Validators.required]),
              });
              this.selectedCourses[i] = parseInt(subject.subjectid);
              this.fac.addControl(this.reqCourseArray, csubject);

            });
          }
          this.loading.endLoading();

        },
        error: (err:any) => console.log(err),
     })
    })
  }

}
