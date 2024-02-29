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
      this.router.navigate(['/Student'])
    }
}
