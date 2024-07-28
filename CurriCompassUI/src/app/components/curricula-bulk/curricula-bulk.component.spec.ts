import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CurriculaBulkComponent } from './curricula-bulk.component';

describe('CurriculaBulkComponent', () => {
  let component: CurriculaBulkComponent;
  let fixture: ComponentFixture<CurriculaBulkComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CurriculaBulkComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CurriculaBulkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
