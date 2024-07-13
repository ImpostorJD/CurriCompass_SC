import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions, yearLevel } from '../../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { StudentFilterPipe } from '../../../services/filter/search-filters/student-filter.pipe';
import { SystemLoadingService } from '../../../services/system-loading.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';

@Component({
  selector: 'app-students-listing',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    StudentFilterPipe,
    DeleteModalPopupComponent,
    LoadingComponentComponent,
  ],
  templateUrl: './students-listing.component.html',
  styleUrl: './students-listing.component.css'
})
export class StudentsListingComponent {
  constructor(
    public loading: SystemLoadingService
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchStudent:string = '';
  students:any = null;
  showError:boolean = false;

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
    this.req.getResource('student-records',
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
