import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-curricula-list',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './curricula-list.component.html',
  styleUrl: './curricula-list.component.css'
})
export class CurriculaListComponent {
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);
}
