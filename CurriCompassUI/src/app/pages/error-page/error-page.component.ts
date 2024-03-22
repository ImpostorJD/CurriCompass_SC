import { Location } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../services/auth.service';


@Component({
  selector: 'app-error-page',
  standalone: true,
  imports: [RouterLink],
  templateUrl: './error-page.component.html',
  styleUrl: './error-page.component.css'
})
export class ErrorPageComponent {
  constructor(private location: Location){}

  auth = inject(AuthService);
  message: string = null!;
  errorCode:string = null!;

  ngOnInit(){
    const urlPath = this.location.path().split('/')[2];

    if(urlPath == "500"){
      this.message = "Something went wrong!";
      this.errorCode = "500";
    }else if(urlPath == "403"){
      this.message = "Forbidden!";
      this.errorCode = "403";
    }else{
      this.errorCode = "404";
      this.message = "Page not found!";
    }
  }
}
