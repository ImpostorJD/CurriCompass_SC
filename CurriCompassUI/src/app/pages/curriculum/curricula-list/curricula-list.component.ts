import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { CurriculumFilterPipe } from '../../../services/search-filters/curriculum-filter.pipe';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth.service';

@Component({
  selector: 'app-curricula-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    CurriculumFilterPipe,
    FormsModule,
  ],

  templateUrl: './curricula-list.component.html',
  styleUrl: './curricula-list.component.css'
})
export class CurriculaListComponent {
  constructor(){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchCurricula: string = '';
  curricula: any  = null;
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);

  getCurricula(){
    this.req.getResource('curriculum', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.curricula = res[1];
      },
      error: err => console.error(err),
    })
  }

  deleteCurricula(id: number){
    this.req.deleteResource('curriculum/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getCurricula()
      },
      error: err => console.error(err),
    })
  }

  ngOnInit(){
    this.getCurricula()
  }
}
