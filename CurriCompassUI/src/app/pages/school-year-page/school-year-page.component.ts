import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';
import { FormatDateService } from '../../services/format/format-date.service';

@Component({
  selector: 'app-school-year-page',
  standalone: true,
  imports: [
    RouterLink,
    HttpClientModule,
  ],
  providers: [
    HttpReqHandlerService,
  ],
  templateUrl: './school-year-page.component.html',
  styleUrl: './school-year-page.component.css'
})
export class SchoolYearPageComponent {
  constructor(
    private req: HttpReqHandlerService,
    public dateformat: FormatDateService,
  ){}

  schoolYears:any = null;
  showError = false;

  getSchoolYear(){
    this.req.getResource('school-year', httpOptions).subscribe({
      next: (res:any) => {
        this.schoolYears = res[1];
      },
      error: err => console.error(err),
    })
  }

  deleteSchoolYear(id: number){
    this.req.deleteResource('school-year/' + id, httpOptions).subscribe({
      next: () => {
        this.getSchoolYear();
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
  }

  ngOnInit(){
    this.getSchoolYear();
  }
}
