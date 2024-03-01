import { TestBed } from '@angular/core/testing';

import { HttpReqHandlerService } from './http-req-handler.service';

describe('HttpReqHandlerService', () => {
  let service: HttpReqHandlerService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(HttpReqHandlerService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
