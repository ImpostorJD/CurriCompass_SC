import { Component, ElementRef, ViewChild, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-login-ui',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule
  ],
  templateUrl: './login-ui.component.html',
  styleUrl: './login-ui.component.css'
})
export class LoginUiComponent {
    constructor(
      private router: Router,
      private fb: FormBuilder,
    ){}

    @ViewChild('password') passwordElement!: ElementRef;

    auth: AuthService = inject(AuthService);
    showPass:boolean = false;

    loginPayload = this.fb.group({
      email: new FormControl('', [Validators.required, Validators.email]),
      password: new FormControl('', [Validators.required]),
    });

    toggleVisibility(e:any){
      if(this.showPass){
        e.target.innerHTML = "password";
      }else{
        e.target.innerHTML = "visibility_lock";
      }
      this.showPass = !this.showPass;
    }

    onLoginAttempt() {
      if(this.loginPayload.status === "INVALID"){
        return;
      }

      this.auth.login(
        this.loginPayload.get('email')?.value!,
        this.loginPayload.get('password')?.value!).subscribe({
          next: async (res:any) => {
            this.auth.setCookie('user', res.authorisation.token);
            const user = await this.auth.getUser();
            if(user.firstlogin){
              this.router.navigate(['/users/change-password']);
            }else{
              this.router.navigate(['/'])
            }

          },
          error: err => {
            if (err.status == 401){
              this.loginPayload.get('password')?.setErrors({'incorrect': true});
            }else if (err.status == 404){
              this.loginPayload.get('email')?.setErrors({'not found': true});
            }
          }
        })
    }

    checkIfActive() {
      const e = this.passwordElement?.nativeElement;

      if(!e){
        return false;
      }
      return e.classList.contains('active');
    }

    makeFormActive(event: any): void {
      if(event.targe?.value?.trim().length !== 0){
        event.target?.classList.add('active');
        return;
      }
      event.target?.classList.remove('active');
    }

    toggleFormActive(event: any): void {
      event.target.children[0].focus();
      this.makeFormActive( event.target.children[0]);
    }
}
