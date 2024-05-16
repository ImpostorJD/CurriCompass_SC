import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { UserFilterPipe } from '../../../services/filter/search-filters/user-filter.pipe';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-users',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    UserFilterPipe,
  ],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css'
})
export class UsersComponent {
  constructor(){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchUser:string ='';
  users: any = null!;

  getUser(){
    this.req.getResource('users', httpOptions(this.auth.getCookie('user')))
      .subscribe({
        next: (res:any) => {
          this.users = res[0];
        },
        error: err => console.error(err),
      });
  }

  deleteUser(id: number){

    if(confirm("Are you sure to delete this User?")){
    this.req.deleteResource('users/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getUser();
      },

      error: error => console.error(error),
    });
  }
  }

  ngOnInit(){
    this.getUser();
  }
}
