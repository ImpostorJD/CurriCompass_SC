import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { ProgramFilterPipe } from '../../../services/filter/search-filters/program-filter.pipe';
import { AuthService } from '../../../services/auth/auth.service';
import { NgConfirmModule, NgConfirmService } from 'ng-confirm-box';

@Component({
  selector: 'app-program-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    ProgramFilterPipe,
    NgConfirmModule
  ],
  templateUrl: './program-list.component.html',
  styleUrl: './program-list.component.css'
})
export class ProgramListComponent {

  constructor(){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

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
    if(confirm("Are you sure to delete this program?")){
    this.req.deleteResource('programs/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getPrograms();
      },

      error: error => console.error(error),
    });

  }

  }
  ngOnInit(){
    this.getPrograms();
  }
}
