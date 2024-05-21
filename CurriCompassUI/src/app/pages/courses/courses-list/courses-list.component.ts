import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { FormsModule } from '@angular/forms';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { httpOptions } from '../../../../configs/Constants';
import { AuthService } from '../../../services/auth/auth.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { ModalUtilityService } from '../../../services/modal-utility.service';

@Component({
  selector: 'app-courses-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CourseFilterPipe,
    FormsModule,
    DeleteModalPopupComponent
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

  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchCourse:string = '';
  courses: any = null;
  showError = false;

  getCourses(){
    this.courseService.getCourses().subscribe((c:any) => {
      this.courses = c.sort((a:any, b:any) => a.subjectcode.localeCompare(b.subjectcode));
    });
  }

  deleteCourse(id: number) {
    this.req.deleteResource('subjects/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getCourses();
      },

      error: err => {
        if (err.status === 400) {
          this.showError = true;
          setTimeout(() => {
            this.showError = false;
          }, 2000);
        }
      },
    });
    this.modalUtility.disableModal();
  }


  ngOnInit() {
    this.getCourses();
  }


}
