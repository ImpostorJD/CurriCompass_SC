import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { HttpClientModule } from '@angular/common/http';
import { httpOptions } from '../../../configs/Constants';
import { CurriculumFilterPipe } from '../../services/search-filters/curriculum-filter.pipe';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-curricula-list',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    HttpClientModule,
    CurriculumFilterPipe,
    FormsModule,
  ],
  providers: [
    HttpReqHandlerService,
  ],
  templateUrl: './curricula-list.component.html',
  styleUrl: './curricula-list.component.css'
})
export class CurriculaListComponent {
  constructor(
    private req: HttpReqHandlerService,
  ){}

  searchCurricula: string = '';
  curricula: any  = null;
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);

  getCurricula(){
    this.req.getResource('curriculum', httpOptions).subscribe({
      next: (res:any) => {
        this.curricula = res[1];
      },
      error: err => console.error(err),
    })
  }

  deleteCurricula(id: number){
    this.req.deleteResource('curriculum/' + id, httpOptions).subscribe({
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
