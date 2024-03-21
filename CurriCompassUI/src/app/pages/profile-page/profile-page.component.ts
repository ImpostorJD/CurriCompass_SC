import { Component, inject } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { RolesToRenderDirective } from '../../services/auth/roles-to-render.directive';
import { FormatDateService } from '../../services/format/format-date.service';

@Component({
  selector: 'app-profile-page',
  standalone: true,
  imports: [RolesToRenderDirective],
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

  ngOnInit(){
    this.auth.checkUser().subscribe({
      next: (res:any) => {
        this.user = res[1];
        console.log(this.user);
      },

      error: error => console.log(error),
    })
  }
}
