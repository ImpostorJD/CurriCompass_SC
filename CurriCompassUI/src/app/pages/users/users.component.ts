import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { HttpClientModule } from '@angular/common/http';
import { httpOptions } from '../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { UserFilterPipe } from '../../services/user-filter.pipe';

@Component({
  selector: 'app-users',
  standalone: true,
  imports: [
    HttpClientModule,
    CommonModule,
    RouterLink,
    FormsModule,
    UserFilterPipe,
  ],
  providers: [
    HttpReqHandlerService,
  ],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css'
})
export class UsersComponent {
  constructor(
    private req: HttpReqHandlerService
  ){}

  searchUser:string ='';
  users: any = null!;

  getUser(){
    this.req.getResource('users', httpOptions).subscribe({
      next: (res:any) => {
        this.users = res[0];
        //console.log(this.users);
      },
      error: err => console.error(err),
    });
  }

  deleteUser(userid:number){
    this.req.deleteResource('users/'+userid, httpOptions).subscribe({
      next: (res:any) => {
        this.getUser();
        //console.log(res);
      },
      error: err => console.error(err),
    });

  }

  ngOnInit(){
    this.getUser();
  }
}
