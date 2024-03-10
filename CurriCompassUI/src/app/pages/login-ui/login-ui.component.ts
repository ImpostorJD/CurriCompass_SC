import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login-ui',
  standalone: true,
  imports: [],
  templateUrl: './login-ui.component.html',
  styleUrl: './login-ui.component.css'
})
export class LoginUiComponent {
    constructor(private router: Router){}
    onLoginSuccess(){
      this.router.navigate(['/'])
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
      //event.target.children[0].classList.toggle('active');
      event.target.children[0].focus();
      this.makeFormActive( event.target.children[0], fieldName);
    }
}
