import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login-ui',
  standalone: true,
  imports: [
    ReactiveFormsModule,
  ],
  providers:[],
  templateUrl: './login-ui.component.html',
  styleUrl: './login-ui.component.css'
})
export class LoginUiComponent {
    constructor(
      private router: Router,
      private fb: FormBuilder,
    ){}

    auth: AuthService = inject(AuthService);

    loginPayload = this.fb.group({
      email: new FormControl(''),
      password: new FormControl(''),
    });

    onLoginSuccess() {
      this.auth.login(
        this.loginPayload.get('email')?.value!,
        this.loginPayload.get('password')?.value!).subscribe({
          next: (res:any) => {
            this.auth.setCookie('user', res.authorisation.token);
            this.router.navigate(['/'])
          },
          error: err => {
            //TODO: Handle error
          }
        })
    }

    makeFormActive(event: any, fieldName: string): void {
      // if (fieldName === 'username') {
      //   this.usernameErrorMessage = '';
      // } else if (fieldName === 'password') {
      //   this.passwordErrorMessage = '';
      // }
      if(event.target.value.trim().length !== 0){
        event.target.classList.add('active');
        return;
      }
      event.target.classList.remove('active');
    }

    toggleFormActive(event: any, fieldName: string): void {
      event.target.children[0].focus();
      this.makeFormActive( event.target.children[0], fieldName);
    }
}
