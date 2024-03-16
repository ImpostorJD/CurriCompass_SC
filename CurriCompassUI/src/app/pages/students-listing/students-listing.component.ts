import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-students-listing',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    HttpClientModule,
  ],
  providers: [
    HttpReqHandlerService,
  ],
  templateUrl: './students-listing.component.html',
  styleUrl: './students-listing.component.css'
})
export class StudentsListingComponent {
  constructor(
    private req: HttpReqHandlerService,
  ){}

  students:any = null;

  deleteStudent(id : number) {
    this.req.deleteResource('student-records/' + id, httpOptions).subscribe({
      next: () => {
        this.getStudents();
      },
      error: err => console.error(err),
    })
  }

  getStudents(){
    this.req.getResource('student-records', httpOptions).subscribe({
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
