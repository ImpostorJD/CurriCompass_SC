import { Component } from '@angular/core';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { HttpClientModule } from '@angular/common/http';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';

@Component({
  selector: 'app-course-form',
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

  courseField = this.fb.group({
    subjectcode: new FormControl('', [Validators.required]),
    subjectname: new FormControl('', [Validators.required]),
    subjectcredits: new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
  });


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

}
