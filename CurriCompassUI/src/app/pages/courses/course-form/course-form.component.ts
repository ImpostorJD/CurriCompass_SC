import { Component, inject } from '@angular/core';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-course-form',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
    CourseFilterPipe,
    FormsModule
  ],
  providers: [
    CoursesServiceService,
    CourseFilterPipe,
  ],
  templateUrl: './course-form.component.html',
  styleUrl: './course-form.component.css'
})
export class CourseFormComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private coursesService: CoursesServiceService,
    private fac: FormArrayControlUtilsService,
    private coursePipe: CourseFilterPipe,
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchCourse: string = '';
  courseList: any = null;
  selectedCourses: Array<any> = [];
  semesters: any = null;

  courseField = this.fb.group({
    subjectcode: new FormControl('', [Validators.required]),
    subjectname: new FormControl('', [Validators.required]),
    subjectcredits: new FormControl('', [Validators.required, Validators.pattern("^[1-9]*$")]),
    subjectunitlab: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    subjectunitlec: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    subjecthourslec: new FormControl('', [Validators.required, Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
    subjecthourslab: new FormControl('', [Validators.required, Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
    semavailability: new FormControl(null, [Validators.required]),
    //completion: new FormControl(null, [Validators.min(0), Validators.max(1)]),
    year_level: new FormControl(null),
    subjects: this.fb.array([]),
  });

  handleSubmit(){
    if(this.courseField.status == "INVALID"){
      markFormGroupAsDirtyAndInvalid(this.courseField);
      return;
    }

    this.req.postResource('subjects', this.courseField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/courses');
      },

      error: err => {
        if(err.status == 409) {
          console.log(err.status);
          this.courseField.get('subjectcode')?.setErrors({duplicate: true});
        }
      }
    })
  }

  get reqCourseArray() {
    return this.courseField.get('subjects') as FormArray;
  }

  popReqCourseArray(index : number){
    this.selectedCourses.splice(index, 1);
    this.fac.popControl(this.reqCourseArray, index);
  }

  courseSelected(index: number, event: any) {
    const courseid = event.target.value;
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

  getReqCourseControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "subjectid");
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

  ngOnInit(){
    this.coursesService.getCourses().subscribe({
      next: (c:any) => {
        this.courseList = c;
      },
      error: (err:any) => console.log(err),
   });

   this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
    next: (s:any) => {
      this.semesters = s[1];
    },
    error: (err:any) => console.log(err),
   });
  }

}
