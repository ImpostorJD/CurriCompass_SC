import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { EllipsisPipe } from '../../services/format/ellipsis.pipe';

@Component({
  selector: 'app-main-header',
  standalone: true,
  imports: [RouterLink, EllipsisPipe],
  templateUrl: './main-header.component.html',
  styleUrl: './main-header.component.css'
})
export class MainHeaderComponent {
  constructor(){}

  auth: AuthService = inject(AuthService);

  user:any;

  ngOnInit() {
    this.auth.checkUser().subscribe({
      next: (res:any) => {
        this.user = res[1];
      },

      error: err => console.log(err),

    });
  }
}
