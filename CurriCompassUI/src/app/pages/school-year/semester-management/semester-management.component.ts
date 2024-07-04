import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { httpOptions } from '../../../../configs/Constants';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { CommonModule } from '@angular/common';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-semester-management',
  standalone: true,
  imports: [
    RouterLink,
    DeleteModalPopupComponent,
    CommonModule,
    LoadingComponentComponent,
  ],
  templateUrl: './semester-management.component.html',
  styleUrl: './semester-management.component.css'
})
export class SemesterManagementComponent {
  constructor(
    public dateformat: FormatDateService,
    public loading: SystemLoadingService
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  modalUtility: ModalUtilityService = inject(ModalUtilityService);
  messages:any = [];

  schoolYearSem:any = null;
  showError = false;

  initializeSchoolYearSem(){
    this.req.postResource('semester-management/generate-latest', {}, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getSchoolYearSem();
      },
      error: (err:any) => {
        if (err.status === 400) {
          let error_messages = err.error[1];
          console.log(error_messages);
          for (const [key, value] of Object.entries(error_messages)) {
            if (key === "student_records"){
              const students = value as { [key: string]: any }; // Type assertion

              for (const [studentKey, studentValue] of Object.entries(students)) {
                this.messages.push(studentKey);
                studentValue.forEach((data: any) => {
                  this.messages.push("Student No: " + data);
                });
              }

            }else{
              this.messages.push(value);
            }
          }
          this.showError = true;
          // setTimeout(() => {
          //   this.showError = false;
          //   this.messages = [];
          // }, 5000);
        }
      },
    });
  }

  resetError(){
    this.showError = false;
    this.messages = [];
  }

  getSchoolYearSem(){
    this.req.getResource('semester-management', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.schoolYearSem = res[1];
        this.loading.endLoading();
      },
      error: err => console.error(err),
    })
  }

  deleteSchoolYearSem(id: number){
    this.loading.initLoading();
    this.req.deleteResource('semester-management/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getSchoolYearSem();
      },
      error: err => {
        if (err.status === 400) {
          this.showError = true;
          this.loading.endLoading();
          setTimeout(() => {
            this.showError = false;
          }, 2000);
        }
      },
    })
    this.modalUtility.disableModal();
  }

  ngOnInit(){
    this.loading.initLoading();
    this.getSchoolYearSem();
  }
}
