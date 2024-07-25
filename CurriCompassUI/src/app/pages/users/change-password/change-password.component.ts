import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { passwordValidator } from '../../../services/validators/password-validator';

@Component({
  selector: 'app-change-password',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
  ],
  templateUrl: './change-password.component.html',
  styleUrl: './change-password.component.css'
})
export class ChangePasswordComponent {

  constructor(
    private router: Router,
    private fb: FormBuilder,
  ){}

  private auth = inject(AuthService);
  private req = inject(HttpReqHandlerService);

  passwordFieldsEnabled: any = {
    'oldPass' : true,
    'newPass': true,
    'confirmPass' : true,
  }

  togglePass(key : any){
    this.passwordFieldsEnabled[key] = !this.passwordFieldsEnabled[key]
  }

  currentUser: any = null;
  changePassPayload = this.fb.group({
    newPasswordField: new FormControl('', [Validators.required, passwordValidator()]),
    confirmPasswordField: new FormControl('', Validators.required),
  });

  handleSubmit() {
    if (this.changePassPayload.status == "INVALID"){
      return;
    }

    if(this.changePassPayload.get('newPasswordField')?.value != this.changePassPayload.get('confirmPasswordField')?.value){
      this.changePassPayload.get('confirmPasswordField')?.setErrors({'mismatch' : true});
      return;
    }

    this.req.postResource('users/change-password/' + this.currentUser.userid, this.changePassPayload.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        alert("please log in again.");
        this.auth.logout().subscribe({
          next: ()=> {
            this.auth.deleteCookie('user');
            this.auth.removeUserContext();
            this.router.navigate(['/login']);
          },
          error: err => console.error(err),
        });
        // this.router.navigate(['/']);
      },
      error: err => console.error(err)
    });

  }

  async ngOnInit(){
    this.currentUser = await this.auth.getUser();
  }
}
