import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { ProgramFilterPipe } from '../../services/search-filters/program-filter.pipe';

@Component({
  selector: 'app-program-list',
  standalone: true,
  imports: [
    HttpClientModule,
    CommonModule,
    RouterLink,
    FormsModule,
    ProgramFilterPipe,
  ],
  providers: [HttpReqHandlerService],
  templateUrl: './program-list.component.html',
  styleUrl: './program-list.component.css'
})
export class ProgramListComponent {
  constructor(
    private req: HttpReqHandlerService,
  ){}

  searchProgram:string = "";
  programs :any = null;

  getPrograms(){
    this.req.getResource('programs', httpOptions).subscribe({
      next: (res: any) => {
        this.programs = res[1];
      },

      error: error => console.error(error),
    });
  }

  deleteProgram(id: number){
    this.req.deleteResource('programs/' + id, httpOptions).subscribe({
      next: () => {
        this.getPrograms();
      },

      error: error => console.error(error),
    });

  }

  ngOnInit(){
    this.getPrograms();
  }
}
