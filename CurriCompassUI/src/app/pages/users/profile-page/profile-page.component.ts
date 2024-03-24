import { Component, inject } from '@angular/core';
import { RolesToRenderDirective } from '../../../services/auth/roles-to-render.directive';
import { FormatDateService } from '../../../services/format/format-date.service';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-profile-page',
  standalone: true,
  imports: [RolesToRenderDirective, CommonModule],
  providers: [FormatDateService],
  templateUrl: './profile-page.component.html',
  styleUrl: './profile-page.component.css'
})
export class ProfilePageComponent {
  constructor(
    public formatDate: FormatDateService,
  ){}

  auth: AuthService = inject(AuthService);

  user:any;

  getSubjectTakenItem(index: number){
    return this.user.student_record.subjects_taken
      .find((s:any) => s.subjectid === index);
  }

  async ngOnInit(){
    this.user = await this.auth.getUser();
  }
}
