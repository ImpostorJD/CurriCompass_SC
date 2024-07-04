import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { UserFilterPipe } from '../../../services/filter/search-filters/user-filter.pipe';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-users',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    UserFilterPipe,
    DeleteModalPopupComponent,
    LoadingComponentComponent,
  ],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css'
})
export class UsersComponent {
  constructor(
    public loading: SystemLoadingService
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchUser:string ='';
  users: any = null!;


  getUser(){
    this.req.getResource('users', httpOptions(this.auth.getCookie('user')))
      .subscribe({
        next: (res:any) => {
          this.users = res[0];
          this.loading.endLoading();
        },
        error: err => console.error(err),
      });
  }

  deleteUser(id: number){
    this.modalUtility.disableModal();
    this.req.deleteResource('users/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.loading.initLoading();
        this.getUser();
      },

      error: error => console.error(error),
    });
  }

  ngOnInit(){
    this.loading.initLoading();
    this.getUser();
  }
}
