import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { CurriculumFilterPipe } from '../../../services/filter/search-filters/curriculum-filter.pipe';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-curricula-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CurriculumFilterPipe,
    FormsModule,
    DeleteModalPopupComponent,
    LoadingComponentComponent,
  ],

  templateUrl: './curricula-list.component.html',
  styleUrl: './curricula-list.component.css'
})
export class CurriculaListComponent {
  constructor(
    public loading: SystemLoadingService
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchCurricula: string = '';
  curricula: any  = null;
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);

  getCurricula(){
    this.req.getResource('curriculum', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.curricula = res[1];
        this.loading.endLoading();
      },
      error: err => console.error(err),
    })
  }

  deleteCurricula(id: number){
    this.loading.initLoading();
    this.req.deleteResource('curriculum/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {

        this.getCurricula();
      },

      error: error => console.error(error),
    });
    this.modalUtility.disableModal();
  }

  ngOnInit(){
    this.loading.initLoading();
    this.getCurricula()
  }
}
