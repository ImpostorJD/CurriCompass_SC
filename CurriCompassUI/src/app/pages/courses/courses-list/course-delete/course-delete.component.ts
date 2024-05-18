import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CourseFilterPipe } from '../../../../services/filter/search-filters/course-pipe.pipe';
import { FormsModule } from '@angular/forms';
import { CoursesServiceService } from '../../../../services/courses-service.service';
import { AuthService } from '../../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../../services/http-req-handler.service';
import { httpOptions } from '../../../../../configs/Constants';

@Component({
  selector: 'app-course-delete',
  standalone: true,
  imports: [    
    CommonModule,
    RouterLink,
    CourseFilterPipe,
    FormsModule,],
  templateUrl: './course-delete.component.html',
  styleUrl: './course-delete.component.css'
})
export class CourseDeleteComponent {
  constructor(
    private courseService: CoursesServiceService
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  searchCourse:string = '';
  courses: any = null;

  getCourses(){
    this.courseService.getCourses().subscribe((c:any) => {
      this.courses = c.sort((a:any, b:any) => a.subjectcode.localeCompare(b.subjectcode));
    });
  }

  deleteCourse(id: number){

    
    this.req.deleteResource('subjects/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getCourses();
      },

      error: error => console.error(error),
    });
  }
  


  ngOnInit() {
    this.getCourses();
  }


}