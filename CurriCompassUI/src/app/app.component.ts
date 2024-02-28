import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";
import { RouterOutlet } from '@angular/router';
import { LoginUiComponent } from "./login-ui/login-ui.component";
import { StudentFormComponent } from "./student-form/student-form.component";


@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet,LoginUiComponent,StudentFormComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'CurriCompassUI';
}
