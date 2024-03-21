import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { CourseFilterPipe } from '../../services/search-filters/course-pipe.pipe';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { CoursesServiceService } from '../../services/courses-service.service';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-courses-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CourseFilterPipe,
    FormsModule,
  ],
  templateUrl: './courses-list.component.html',
  styleUrl: './courses-list.component.css'
})
export class CoursesListComponent {
  constructor(
    private courseService: CoursesServiceService
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  searchCourse:string = '';
  courses: any = null;

  getCourses(){
    this.courseService.getCourses().subscribe((c:any) => {
      this.courses = c;
    });
  }

  deleteCourse(id: number){
    this.req.deleteResource('subjects/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
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
