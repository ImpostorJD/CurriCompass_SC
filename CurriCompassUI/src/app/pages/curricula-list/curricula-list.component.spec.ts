import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CurriculaListComponent } from './curricula-list.component';

describe('CurriculaListComponent', () => {
  let component: CurriculaListComponent;
  let fixture: ComponentFixture<CurriculaListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CurriculaListComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CurriculaListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
