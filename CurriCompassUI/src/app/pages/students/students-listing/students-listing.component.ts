import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { UserFilterPipe } from '../../../services/filter/search-filters/user-filter.pipe';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-students-listing',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    UserFilterPipe,
  ],
  templateUrl: './students-listing.component.html',
  styleUrl: './students-listing.component.css'
})
export class StudentsListingComponent {
  constructor(
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchStudent:string = '';
  students:any = null;

  deleteStudent(id: number){

    if(confirm("Are you sure to delete this Students Record?")){
    this.req.deleteResource('student-records/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getStudents();
      },

      error: error => console.error(error),
    });
  }
  }

  getStudents(){
    this.req.getResource('student-records',
    httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.students = res[1];
      },

      error : err => console.error(err),
    });
  }

  ngOnInit(){
    this.getStudents();
  }
}
