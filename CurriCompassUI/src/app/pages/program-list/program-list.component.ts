import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-program-list',
  standalone: true,
  imports: [RouterLink, CommonModule],
  templateUrl: './program-list.component.html',
  styleUrl: './program-list.component.css'
})
export class ProgramListComponent {
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);
}
