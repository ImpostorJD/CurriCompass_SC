import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { AuthService } from '../../../services/auth/auth.service';
import { idValidator } from '../../../services/validators/id-validator';
import { emailDomainValidator } from '../../../services/validators/domain-validator';


@Component({
  selector: 'app-student-form',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    ReactiveFormsModule,
  ],

  templateUrl: './student-form.component.html',
  styleUrl: './student-form.component.css'
})
export class StudentFormComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  userField =  this.fb.group({
    "studentid" : new FormControl('', [Validators.required, idValidator()]),
    "userfname" : new FormControl('', [Validators.required, Validators.pattern(/^[A-Za-zÀ-ÖØ-öø-ÿ]+([ \'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*[A-Za-zÀ-ÖØ-öø-ÿ]$/)]),
    "userlname" : new FormControl('', [Validators.required, Validators.pattern(/^[A-Za-zÀ-ÖØ-öø-ÿ]+([ \'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*[A-Za-zÀ-ÖØ-öø-ÿ]$/)]),
    "usermiddle" : new FormControl('', [Validators.pattern(/^[A-Za-zÀ-ÖØ-öø-ÿ]+([ \'-][A-Za-zÀ-ÖØ-öø-ÿ]+)*[A-Za-zÀ-ÖØ-öø-ÿ]$/)]),
    "contactno" : new FormControl('', [Validators.required, Validators.pattern(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)]),
    "email" : new FormControl('', [Validators.required, Validators.email, emailDomainValidator()]),
    "password" : new FormControl('', [Validators.required]),
    // "status" : new FormControl(null, [Validators.required]),
    "roles" : this.fb.array([
      this.fb.group({
        roleid: new FormControl(3)
      })
    ]),
  })

  handleSubmit(event:string) {
    if(this.userField.status == "INVALID"){
      markFormGroupAsDirtyAndInvalid(this.userField);
      return;
    }

    this.req.postResource('student-records', this.userField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {

        if(event === "with_record"){
          this.router.navigateByUrl(`/students/${res[1].student_no}`)
          return;
        }
        this.router.navigateByUrl(`/students`)
      },

      error: (err) => {
          if(err.status == 409) {
            if(err.error[1].email != null){
              this.userField.get('email')?.setErrors({duplicate: true});
            }
            if(err.error[1].studentid != null){
              this.userField.get('studentid')?.setErrors({duplicate: true});
            }
          }
      }
    });
  }
}
