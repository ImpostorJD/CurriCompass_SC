import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { ProgramFilterPipe } from '../../../services/filter/search-filters/program-filter.pipe';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';

@Component({
  selector: 'app-program-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    ProgramFilterPipe,
    DeleteModalPopupComponent
  ],
  templateUrl: './program-list.component.html',
  styleUrl: './program-list.component.css'
})
export class ProgramListComponent {

  constructor(){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchProgram:string = "";
  programs :any = null;

  getPrograms(){
    this.req.getResource('programs', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.programs = res[1];
      },

      error: error => console.error(error),
    });
  }

  deleteProgram(id: number){
    this.req.deleteResource('programs/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getPrograms();
      },

      error: error => console.error(error),
    });
    this.modalUtility.disableModal();
  }
  ngOnInit(){
    this.getPrograms();
  }
}
