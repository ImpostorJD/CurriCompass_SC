import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { StudentFilterPipe } from '../../../services/filter/search-filters/student-filter.pipe';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { StudentBulkRegistrationModalComponent } from '../../../components/student-bulk-registration-modal/student-bulk-registration-modal.component';
import { SystemLoadingService } from '../../../services/system-loading.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { httpOptions, yearLevel } from '../../../../configs/Constants';

@Component({
  selector: 'app-pending-registration-student',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    StudentFilterPipe,
    DeleteModalPopupComponent,
    LoadingComponentComponent,
    StudentBulkRegistrationModalComponent
  ],
  templateUrl: './pending-registration-student.component.html',
  styleUrl: './pending-registration-student.component.css'
})
export class PendingRegistrationStudentComponent {
  constructor(
    public loading: SystemLoadingService
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchStudent:string = '';
  students:any = null;
  showError:boolean = false;
  importModalShow: boolean = false;
  deleteStudent(id: number){
    this.modalUtility.disableModal();
    this.req.deleteResource('student-records/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.loading.initLoading();
        this.getStudents();
      },
      error: error => {
        if (error.status === 409){
          this.loading.endLoading();
          this.showError = true;
        }
      },
    });
  }

  getStudents(){
    this.loading.initLoading();
    this.req.getResource('student-records/pending',
    httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.students = res[1].sort((a: any, b: any) => yearLevel(a.student_record?.year_level?.year_level_desc, b.student_record?.year_level?.year_level_desc));
        this.loading.endLoading();
      },

      error : err => console.error(err),
    });
  }

  ngOnInit(){
    this.loading.initLoading();
    this.getStudents();
  }
}
