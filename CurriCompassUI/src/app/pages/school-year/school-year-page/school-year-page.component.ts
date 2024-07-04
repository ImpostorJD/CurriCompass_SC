import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-school-year-page',
  standalone: true,
  imports: [
    RouterLink,
    DeleteModalPopupComponent,
    LoadingComponentComponent,
  ],
  templateUrl: './school-year-page.component.html',
  styleUrl: './school-year-page.component.css'
})
export class SchoolYearPageComponent {
  constructor(
    public dateformat: FormatDateService,
    public loading: SystemLoadingService,
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  schoolYears:any = null;
  showError = false;

  getSchoolYear(){
    this.req.getResource('school-year', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.schoolYears = res[1];
        this.loading.endLoading();
      },
      error: err => console.error(err),
    })
  }

  deleteSchoolYear(id: number){
    this.loading.initLoading();
    this.req.deleteResource('school-year/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getSchoolYear();
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
    this.getSchoolYear();
  }
}
