import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';

@Component({
  selector: 'app-edit-course',
  standalone: true,
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
  ],
  providers: [
    HttpReqHandlerService,
    RemoveInputErrorService,
  ],
  templateUrl: './edit-course.component.html',
  styleUrl: './edit-course.component.css'
})
export class EditCourseComponent {
  constructor(
    private router: Router,
    private activeRouter: ActivatedRoute,
    private req: HttpReqHandlerService,
    private fb: FormBuilder,
    public rs: RemoveInputErrorService,
  ){}

  courseList: any = null;
  selectedCourses: Array<any> = [];
  routerId: number = null!;

  courseField = this.fb.group({
    subjectcode: new FormControl('', [Validators.required]),
    subjectname: new FormControl('', [Validators.required]),
    subjectcredits: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
    subjecttype: new FormControl(null, [Validators.required]),
    completion: new FormControl(null),
    year_level: new FormControl(null),
    subjects: this.fb.array([]),
  });

  get reqCourseArray() {
    return this.courseField.get('subjects') as FormArray  ;
  }
  popReqCourseArray(index : number){
    this.reqCourseArray.removeAt(index);
  }

  courseSelected(index: number, event: any) {
    const courseid = event.target.value;
    this.selectedCourses[index] = parseInt(courseid);
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

  handleSubmit(){

    console.log(this.courseField.value);
    return;
    if(this.courseField.status == "INVALID"){
      markFormGroupAsDirtyAndInvalid(this.courseField);
      return;
    }

    this.req.patchResource('subjects/' + this.routerId, this.courseField.value, httpOptions).subscribe({
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

  ngOnInit(){

    this.activeRouter.params.subscribe(params => {
      this.routerId = parseInt(params['id']);
      this.req.getResource('subjects', httpOptions).subscribe({
        next: (res:any) => {
          this.courseList = res[1].filter((obj:any) => obj.subjectid !== this.routerId);
        },

        error: err => console.error(err),
      });

      this.req.getResource('subjects/' + this.routerId, httpOptions).subscribe({
        next: (res: any) => {
          this.courseField.patchValue(res[1]);
          this.courseField.controls['year_level'].patchValue(res[1].pre_requisites.year_level);
          this.courseField.controls['completion'].patchValue(res[1].pre_requisites.completion);
          if (res[1].pre_requisites_subjects.length > 0) {
            res[1].pre_requisites_subjects.forEach((subject:any, i: number) => {
              const csubject: any = this.fb.group({
                'subjectid' : new FormControl(parseInt(subject.subjectid), [Validators.required]),
              });
              this.selectedCourses[i] = parseInt(subject.subjectid);
              this.reqCourseArray.push(csubject);

            });
          }
        },
        error: error => console.log(error),
      })

    })
  }

}
