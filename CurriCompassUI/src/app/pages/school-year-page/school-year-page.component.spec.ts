import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SchoolYearPageComponent } from './school-year-page.component';

describe('SchoolYearPageComponent', () => {
  let component: SchoolYearPageComponent;
  let fixture: ComponentFixture<SchoolYearPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SchoolYearPageComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(SchoolYearPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
