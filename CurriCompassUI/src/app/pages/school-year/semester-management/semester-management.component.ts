import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { httpOptions } from '../../../../configs/Constants';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';

@Component({
  selector: 'app-semester-management',
  standalone: true,
  imports: [
    RouterLink,
    DeleteModalPopupComponent
  ],
  templateUrl: './semester-management.component.html',
  styleUrl: './semester-management.component.css'
})
export class SemesterManagementComponent {
  constructor(
    public dateformat: FormatDateService,
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  schoolYearSem:any = null;
  showError = false;

  getSchoolYearSem(){
    this.req.getResource('semester-management', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.schoolYearSem = res[1];
      },
      error: err => console.error(err),
    })
  }

  deleteSchoolYearSem(id: number){
    this.req.deleteResource('semester-management/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getSchoolYearSem();
      },
      error: err => {
        if (err.status === 400) {
          this.showError = true;
          setTimeout(() => {
            this.showError = false;
          }, 2000);
        }
      },
    })
    this.modalUtility.disableModal();
  }

  ngOnInit(){
    this.getSchoolYearSem();
  }
}
