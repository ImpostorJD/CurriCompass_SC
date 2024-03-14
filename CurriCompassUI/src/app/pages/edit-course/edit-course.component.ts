import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
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

  routerId: number = null!;

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
      this.req.getResource('subjects/' + this.routerId, httpOptions).subscribe({
        next: (res: any) => {
          this.courseField.patchValue(res[1]);
        },
        error: error => console.log(error),
      })
    })
  }

}
