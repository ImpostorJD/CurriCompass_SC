import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';
import { CourseFilterPipe } from '../../services/search-filters/course-pipe.pipe';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { CoursesServiceService } from '../../services/courses-service.service';

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

  providers: [
    HttpReqHandlerService,
    AuthService,
  ],
  templateUrl: './courses-list.component.html',
  styleUrl: './courses-list.component.css'
})
export class CoursesListComponent {
  constructor(
    private req: HttpReqHandlerService,
    private auth: AuthService,
    private courseService: CoursesServiceService
  ){}

  searchCourse:string = '';
  courses: any = null;

  getCourses(){
    this.courseService.getCourses().subscribe((c:any) => {
      this.courses = c;
    });
  }

  deleteCourse(id: number){
    this.req.deleteResource('subjects/' + id, this.auth.getCookie('user')).subscribe({
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
