import { Component, inject } from '@angular/core';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CourseAvailableFilterPipe } from '../../../services/filter/search-filters/course-available-filter.pipe';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-course-availability',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CourseAvailableFilterPipe,
    FormsModule
  ],
  templateUrl: './course-availability.component.html',
  styleUrl: './course-availability.component.css'
})
export class CourseAvailabilityComponent {
  constructor(){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  courses: any = null;
  semesters: any = null;

  searchCourse:string = '';

  getCourseAvailability(){
    this.req.postResource('course-availability', {}, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.courses = res[1].sort((a:any,b:any) => {
          if (a.semid !== b.semid) {
            return a.semid - b.semid;
          } else {
            return a.subjects.subjectcode.localeCompare(b.subjects.subjectcode);
          }
        });
      },
      error: err => console.error(err),
    });
  }

  ngOnInit() {
    //this.getCourseAvailability();
  }
}
