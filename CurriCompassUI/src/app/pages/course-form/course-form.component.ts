import { Component } from '@angular/core';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { HttpClientModule } from '@angular/common/http';
import { FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';
import { CoursePipePipe } from '../../services/search-filters/course-pipe.pipe';

//TODO: Add role-based access
@Component({
  selector: 'app-course-form',
  standalone: true,
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
    CoursePipePipe,
    FormsModule
  ],
  providers: [
    HttpReqHandlerService,
    RemoveInputErrorService,
  ],
  templateUrl: './course-form.component.html',
  styleUrl: './course-form.component.css'
})
export class CourseFormComponent {
  constructor(
    private router: Router,
    private req: HttpReqHandlerService,
    private fb: FormBuilder,
    public rs: RemoveInputErrorService
  ){}

  searchCourse: string = '';
  courseList: any = null;
  selectedCourses: Array<any> = [];

  courseField = this.fb.group({
    subjectcode: new FormControl('', [Validators.required]),
    subjectname: new FormControl('', [Validators.required]),
    subjectcredits: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    subjecttype: new FormControl(null, [Validators.required]),
    completion: new FormControl(null, [Validators.min(0), Validators.max(1)]),
    year_level: new FormControl(null),
    subjects: this.fb.array([]),
  });

  getSelectedCourseIndex(index: number): number | null {
    const selectedCourseId = this.selectedCourses[index];
    if (selectedCourseId === null) {
      return null;
    }
    return this.courseList.find((c: any) => c.subjectid === selectedCourseId)?.subjectid || null;
  }

  handleSubmit(){
    if(this.courseField.status == "INVALID"){
      markFormGroupAsDirtyAndInvalid(this.courseField);
      return;
    }

    this.req.postResource('subjects', this.courseField.value, httpOptions).subscribe({
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
    this.reqCourseArray.removeAt(index);
  }

  courseSelected(index: number, event: any) {
    const courseid = event.target.value;
    //this.selectedCourses[index] = parseInt(courseid);
    this.selectedCourses[index] = courseid ? parseInt(courseid) : null;
  }

  isCourseSelected(courseid: number): boolean {
    return this.selectedCourses.includes(courseid);
  }

  addReqCourseArray() {
    const csubject: any = this.fb.group({
      'subjectid' : new FormControl(null, [Validators.required]),
    });
    this.reqCourseArray.push(csubject);
  }

  getReqCourseControl(index: number): FormControl{
    return (this.reqCourseArray.at(index) as FormGroup).get('subjectid')! as FormControl;
  }


  ngOnInit(){
    this.req.getResource('subjects', httpOptions).subscribe({
      next: (res:any) => {
        this.courseList = res[1];
      },

      error: err => console.error(err),
    });
  }

}
