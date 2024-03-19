import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';
import { CourseFilterPipe } from '../../services/search-filters/course-pipe.pipe';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-courses-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    HttpClientModule,
    CourseFilterPipe,
    FormsModule,
  ],

  providers: [HttpReqHandlerService],
  templateUrl: './courses-list.component.html',
  styleUrl: './courses-list.component.css'
})
export class CoursesListComponent {
  constructor(
    private req: HttpReqHandlerService,
  ){}

  searchCourse:string = '';
  courses: any = null;

  getCourses(){
    this.req.getResource('subjects', httpOptions).subscribe({
      next: (res: any) => {
        this.courses = res[1];
      },
      error: err => console.error(err)
    });
  }

  deleteCourse(id: number){
    this.req.deleteResource('subjects/' + id, httpOptions).subscribe({
      next: () => {
        this.getCourses()
      },
      error: err => console.error(err),
    })
  }

  ngOnInit() {
    this.getCourses();
  }


}
