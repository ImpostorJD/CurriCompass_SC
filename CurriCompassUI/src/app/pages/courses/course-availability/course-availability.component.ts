import { Component, inject } from '@angular/core';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CourseAvailableFilterPipe } from '../../../services/filter/search-filters/course-available-filter.pipe';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-course-availability',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CourseAvailableFilterPipe,
    FormsModule,
    DeleteModalPopupComponent,
    LoadingComponentComponent,
  ],
  templateUrl: './course-availability.component.html',
  styleUrl: './course-availability.component.css'
})
export class CourseAvailabilityComponent {
  constructor(
    public loading: SystemLoadingService
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  courses: any = null;
  semesters: any = null;
  showError = false;
  searchCourse:string = '';

  deleteSchoolYearSem(id: number){
    this.loading.initLoading();
    this.req.deleteResource('course-availability/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getCourseAvailability();
      },
      error: err => {
        if (err.status === 400) {
          this.loading.endLoading();
          this.showError = true;
          setTimeout(() => {
            this.showError = false;
          }, 5000);
        }
      },
    })
    this.modalUtility.disableModal();
  }

  getCourseAvailability(){
    this.req.getResource('course-availability', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        if(res[1]){
          this.courses = res[1].sort((a:any,b:any) => {
            if (a.semester_sy.semester.semid !== b.semester_sy.semester.semid) {
              return a.semester_sy.semester.semid - b.semester_sy.semester.semid;
            } else {
              return a.subjects.subjectcode.localeCompare(b.subjects.subjectcode);
            }
          });
        }

        this.loading.endLoading();

      },
      error: err => console.error(err),
    });
  }

  ngOnInit() {
    this.loading.initLoading();
    this.getCourseAvailability();
  }
}
