import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions, yearLevel } from '../../../../configs/Constants';
import { FormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';
import { ModalUtilityService } from '../../../services/modal-utility.service';
import { DeleteModalPopupComponent } from '../../../components/delete-modal-popup/delete-modal-popup.component';
import { StudentFilterPipe } from '../../../services/filter/search-filters/student-filter.pipe';

@Component({
  selector: 'app-students-listing',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    FormsModule,
    StudentFilterPipe,
    DeleteModalPopupComponent
  ],
  templateUrl: './students-listing.component.html',
  styleUrl: './students-listing.component.css'
})
export class StudentsListingComponent {
  constructor(
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);
  modalUtility: ModalUtilityService = inject(ModalUtilityService);

  searchStudent:string = '';
  students:any = null;

  deleteStudent(id: number){
    this.modalUtility.disableModal();
    this.req.deleteResource('student-records/' + id, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getStudents();
      },

      error: error => console.error(error),
    });
  }

  getStudents(){
    this.req.getResource('student-records',
    httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.students = res[1].sort((a: any, b: any) => yearLevel(a.student_record?.year_level?.year_level_desc, b.student_record?.year_level?.year_level_desc));
      },

      error : err => console.error(err),
    });
  }

  ngOnInit(){
    this.getStudents();
  }
}
