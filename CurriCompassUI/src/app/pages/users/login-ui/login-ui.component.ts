import { Component, ElementRef, ViewChild, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-login-ui',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule,
    RouterLink
  ],
  templateUrl: './login-ui.component.html',
  styleUrls: ['./login-ui.component.css']
})
export class LoginUiComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,

  ){}

  @ViewChild('password') passwordElement!: ElementRef;

  auth: AuthService = inject(AuthService);
  showPass: boolean = false;

  attempt = 0;
  maxAttempts = 3;
  lockoutDuration = 60; // 60 seconds
  isLockedOut = false;
  remainingTime = 0;

  loginPayload = this.fb.group({
    email: new FormControl('', [Validators.required, Validators.email]),
    password: new FormControl('', [Validators.required]),
  });

  toggleVisibility(e: any){
    if(this.showPass){
      e.target.innerHTML = "password";
    }else{
      e.target.innerHTML = "visibility_lock";
    }
    this.showPass = !this.showPass;
  }

  onLoginAttempt() {
    if (this.isLockedOut) {
      return;
    }

    if(this.loginPayload.status === "INVALID"){
      return;
    }

    this.auth.login(
      this.loginPayload.get('email')?.value!,
      this.loginPayload.get('password')?.value!).subscribe({
        next: async (res: any) => {
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
          }else if(err.status == 409){
            this.loginPayload.get('email')?.setErrors({'inactive': true});
          }
          this.attempt++;
          if (this.attempt >= this.maxAttempts) {
            this.startCountdown();
          }
        }
      });
  }

  startCountdown() {
    this.isLockedOut = true;
    this.remainingTime = this.lockoutDuration;

    const interval = setInterval(() => {
      this.remainingTime--;
      if (this.remainingTime <= 0) {
        clearInterval(interval);
        this.isLockedOut = false;
        this.attempt = 0;
      }
    }, 1000);
  }

  checkIfActive() {
    const e = this.passwordElement?.nativeElement;

    if (!e) {
      return false;
    }
    return e.classList.contains('active');
  }

  makeFormActive(event: any): void {
    if(event.target?.value?.trim().length !== 0){
      event.target?.classList.add('active');
      return;
    }
    event.target?.classList.remove('active');
  }

  toggleFormActive(event: any): void {
    event.target.children[0].focus();
    this.makeFormActive(event.target.children[0]);
  }
}
