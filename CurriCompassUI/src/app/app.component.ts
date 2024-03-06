import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";
import { RouterOutlet } from '@angular/router';
import { LoginUiComponent } from "./pages/login-ui/login-ui.component";
import { StudentFormComponent } from "./pages/student-form/student-form.component";
import { NavigationComponent } from "./components/navigation/navigation.component";
import { BaselayoutComponent } from "./components/baselayout/baselayout.component";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    BaselayoutComponent
  ],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'CurriCompassUI';
}
