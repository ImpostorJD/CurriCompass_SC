import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-program-list',
  standalone: true,
  imports: [
    HttpClientModule,
    CommonModule,
    RouterLink,
  ],
  providers: [HttpReqHandlerService],
  templateUrl: './program-list.component.html',
  styleUrl: './program-list.component.css'
})
export class ProgramListComponent {
  constructor(
    private req: HttpReqHandlerService,
  ){}

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
